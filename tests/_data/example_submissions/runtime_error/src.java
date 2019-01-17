import java.util.Scanner;

class Main{
    //adds two numbers
    public static void main(String[] args){
		Scanner reader = new Scanner(System.in);
		int one, two;
		one = reader.nextInt();
		if (one == 0)
			throw new RuntimeException("Hi mom");
   		two = reader.nextInt();
   		System.out.println(one+two);
    }
}

